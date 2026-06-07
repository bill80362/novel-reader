<?php

use App\Mcp\Servers\NovelServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp', NovelServer::class)
    ->middleware('mcp.auth');
