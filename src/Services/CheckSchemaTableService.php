<?php

namespace HaMinh7036\Services;

use Illuminate\Support\Facades\DB;

class CheckSchemaTableService
{
    /**
     * Check missing tables/schemas on multiple servers
     * @param array $servers
     * @param array $tables
     * @return array
     */
    public function checkMissingTables(array $servers, array $tables): array
    {
        $missing = [];
        // $servers: [['server_name', 'connection_name']...]
        // $tables: [['server_name', 'schema_name', 'table_name']...]
        // Build a map: server_name => connection_name
        $serverMap = [];
        foreach ($servers as $row) {
            $serverName = $row[0] ?? null;
            $connectionName = $row[1] ?? null;
            if ($serverName && $connectionName) {
                $serverMap[$serverName] = $connectionName;
            }
        }

        foreach ($tables as $tableRow) {
            $serverName = $tableRow[0] ?? null;
            $schemaName = $tableRow[1] ?? null;
            $tableName = $tableRow[2] ?? null;
            if (!$serverName || !$schemaName || !$tableName) {
                continue;
            }

            $connection = $serverMap[$serverName] ?? null;
            if (!$connection) {
                $missing[] = [
                    'server_name' => $serverName,
                    'schema_name' => $schemaName,
                    'table_name' => $tableName,
                    'error' => 'No connection found for server',
                ];
                continue;
            }

            try {
                $exists = $this->tableExists($connection, $schemaName, $tableName);
                if (!$exists) {
                    $missing[] = [
                        'server_name' => $serverName,
                        'schema_name' => $schemaName,
                        'table_name' => $tableName,
                    ];
                }
            } catch (\Throwable $e) {
                $missing[] = [
                    'server_name' => $serverName,
                    'schema_name' => $schemaName,
                    'table_name' => $tableName,
                    'error' => $e->getMessage(),
                ];
            }
        }
        return $missing;
    }

    /**
     * Check if table exists in schema for a connection
     * @param string $connection
     * @param string $schema
     * @param string $table
     * @return bool
     */
    private function tableExists(string $connection, string $schema, string $table): bool
    {
        $result = DB::connection($connection)->selectOne(
            'SELECT COUNT(*) as cnt FROM information_schema.tables WHERE table_schema = ? AND table_name = ?',
            [$schema, $table]
        );
        return ($result && $result->cnt > 0);
    }
}