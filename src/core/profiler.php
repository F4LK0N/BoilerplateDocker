<? defined("FKN") or http_response_code(403).die('Forbidden!');

### PROFILER ###

//Time Start (Unix Timestamp)
define("PROFILER_TIMING", microtime(true));

//Memory Start (Bytes)
define("PROFILER_MEMORY", memory_get_usage(false));
define("PROFILER_MEMORY_REAL", memory_get_usage(true));
