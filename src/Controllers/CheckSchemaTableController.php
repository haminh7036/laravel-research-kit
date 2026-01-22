<?php

namespace HaMinh7036\Controllers;

use HaMinh7036\Services\CheckSchemaTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckSchemaTableController
{
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'server_csv' => 'required|file|mimes:csv,txt',
            'table_csv' => 'required|file|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $serverFile = $request->file('server_csv');
        $tableFile = $request->file('table_csv');

        $servers = $this->parseCsv($serverFile);
        $tables = $this->parseCsv($tableFile);

        $service = new CheckSchemaTableService();
        $missing = $service->checkMissingTables($servers, $tables);

        return response()->json(['missing' => $missing]);
    }

    private function parseCsv($file)
    {
        $rows = [];
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }
        return $rows;
    }
}
