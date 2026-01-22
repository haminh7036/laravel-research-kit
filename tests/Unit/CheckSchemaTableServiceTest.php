<?php

namespace Tests\Unit;

use HaMinh7036\Services\CheckSchemaTableService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CheckSchemaTableServiceTest extends TestCase
{
    public function testCheckMissingTables()
    {
        $servers = [
            ['server1', 'connection1'],
            ['server2', 'connection2'],
        ];

        $tables = [
            ['server1', 'schema1', 'table1'],
            ['server2', 'schema2', 'table2'],
            ['server3', 'schema3', 'table3'],
        ];

        DB::shouldReceive('connection->selectOne')
            ->with('SELECT COUNT(*) as cnt FROM information_schema.tables WHERE table_schema = ? AND table_name = ?', ['schema1', 'table1'])
            ->andReturn((object) ['cnt' => 1]);

        DB::shouldReceive('connection->selectOne')
            ->with('SELECT COUNT(*) as cnt FROM information_schema.tables WHERE table_schema = ? AND table_name = ?', ['schema2', 'table2'])
            ->andReturn((object) ['cnt' => 0]);

        $service = new CheckSchemaTableService();
        $result = $service->checkMissingTables($servers, $tables);

        $this->assertCount(2, $result);
        $this->assertEquals('server2', $result[0]['server_name']);
        $this->assertEquals('schema2', $result[0]['schema_name']);
        $this->assertEquals('table2', $result[0]['table_name']);
        $this->assertEquals('server3', $result[1]['server_name']);
        $this->assertEquals('schema3', $result[1]['schema_name']);
        $this->assertEquals('table3', $result[1]['table_name']);
    }
}