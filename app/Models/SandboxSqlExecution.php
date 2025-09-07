<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SandboxSqlExecution extends Model
{
    use HasFactory;

    protected $fillable = [
        'sandbox_name',
        'sql_query',
        'query_type',
        'status',
        'result',
        'error_message',
        'affected_rows',
        'execution_time_ms',
        'user_session_id',
    ];

    protected $casts = [
        'result' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function logExecution(
        string $sandboxName,
        string $sqlQuery,
        string $queryType,
        string $status,
        $result = null,
        string $errorMessage = null,
        int $affectedRows = null,
        int $executionTimeMs = null
    ) {
        return static::create([
            'sandbox_name' => $sandboxName,
            'sql_query' => $sqlQuery,
            'query_type' => $queryType,
            'status' => $status,
            'result' => $result,
            'error_message' => $errorMessage,
            'affected_rows' => $affectedRows,
            'execution_time_ms' => $executionTimeMs,
            'user_session_id' => session()->getId(),
        ]);
    }

    public static function getQueryType(string $sql): string
    {
        $sql = trim(strtoupper($sql));
        
        if (preg_match('/^SELECT\s/', $sql)) return 'SELECT';
        if (preg_match('/^INSERT\s/', $sql)) return 'INSERT';
        if (preg_match('/^UPDATE\s/', $sql)) return 'UPDATE';
        if (preg_match('/^DELETE\s/', $sql)) return 'DELETE';
        if (preg_match('/^CREATE\s/', $sql)) return 'CREATE';
        if (preg_match('/^DROP\s/', $sql)) return 'DROP';
        if (preg_match('/^ALTER\s/', $sql)) return 'ALTER';
        
        return 'OTHER';
    }
}