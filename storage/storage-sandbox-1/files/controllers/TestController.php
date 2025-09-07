<?php

class TestController
{
    public function index()
    {
        return [
            'message' => 'Hello from TestController',
            'timestamp' => now(),
            'data' => [
                'test' => true,
                'version' => '1.0'
            ]
        ];
    }
    
    public function show($id)
    {
        return [
            'id' => $id,
            'message' => "Showing item with ID: {$id}",
            'timestamp' => now()
        ];
    }
}