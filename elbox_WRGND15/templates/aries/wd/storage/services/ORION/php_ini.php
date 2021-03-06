<?
fwrite("w", $PHP_INI, "; PHP configurations\n");
fwrite("a", $PHP_INI, '
[PHP]
user_ini.filename =
engine = On
short_open_tag = Off
asp_tags = Off
precision = 4
y2k_compliance = On
output_buffering = Off
zlib.output_compression = 4096
zlib.output_compression_level = -1
implicit_flush = Off
unserialize_callback_func =
serialize_precision = 10
allow_call_time_pass_reference = Off
safe_mode = Off
safe_mode_gid = Off
safe_mode_include_dir =
safe_mode_exec_dir =
safe_mode_allowed_env_vars = PHP_
safe_mode_protected_env_vars = LD_LIBRARY_PATH
;open_basedir =
disable_functions =
disable_classes =
;ignore_user_abort = On
;realpath_cache_size = 16k
;realpath_cache_ttl = 120
expose_php = On
max_execution_time = 15
max_input_time = 60
;max_input_nesting_level = 64
memory_limit = 8M
error_reporting = E_ERROR
display_errors = Off
display_startup_errors = On
log_errors = On
log_errors_max_len = 1024
;error_log = /var/log/php_errors.log
ignore_repeated_errors = Off
ignore_repeated_source = Off
report_memleaks = On
;report_zend_debug = 0
track_errors = On
html_errors = On
variables_order = "EGPCS"
request_order = "GP"
register_globals = Off
register_long_arrays = Off
register_argc_argv = Off
auto_globals_jit = On
post_max_size = 1G
magic_quotes_gpc = Off
magic_quotes_runtime = Off
magic_quotes_sybase = Off
auto_prepend_file =
auto_append_file =
default_mimetype = "text/html"
default_charset = "utf-8"
doc_root =
user_dir =
enable_dl = On
file_uploads = On
upload_tmp_dir = "/var/tmp/storage/Public/.uploads"
upload_max_filesize = 1G
allow_url_fopen = On
allow_url_include = Off
default_socket_timeout = 60
auto_detect_line_endings = On
extension=
[Date]
date.timezone = "Asia/Taipei"
date.default_latitude = 31.5167
date.default_longitude = 121.4500
[filter]
[iconv]
[intl]
[sqlite]
[sqlite3]
[Pcre]
[Pdo]
[Pdo_mysql]
pdo_mysql.cache_size = 2000
pdo_mysql.default_socket=
[Phar]
[Syslog]
define_syslog_variables  = Off
[mail function]
SMTP = localhost
smtp_port = 25
mail.add_x_header = On
[SQL]
sql.safe_mode = Off
[ODBC]
odbc.allow_persistent = On
odbc.check_persistent = On
odbc.max_persistent = -1
odbc.max_links = -1
odbc.defaultlrl = 4096
odbc.defaultbinmode = 1
[Interbase]
ibase.allow_persistent = 1
ibase.max_persistent = -1
ibase.max_links = -1
ibase.timestampformat = "%Y-%m-%d %H:%M:%S"
ibase.dateformat = "%Y-%m-%d"
ibase.timeformat = "%H:%M:%S"
[MySQL]
mysql.allow_local_infile = On
mysql.allow_persistent = On
mysql.cache_size = 2000
mysql.max_persistent = -1
mysql.max_links = -1
mysql.default_port =
mysql.default_socket =
mysql.default_host =
mysql.default_user =
mysql.default_password =
mysql.connect_timeout = 60
mysql.trace_mode = Off
[MySQLi]
mysqli.max_persistent = -1
mysqli.max_links = -1
mysqli.cache_size = 2000
mysqli.default_port = 3306
mysqli.default_socket =
mysqli.default_host =
mysqli.default_user =
mysqli.default_pw =
mysqli.reconnect = Off
[mysqlnd]
mysqlnd.collect_statistics = On
mysqlnd.collect_memory_statistics = On
[OCI8]
[PostgresSQL]
pgsql.allow_persistent = On
pgsql.auto_reset_persistent = Off
pgsql.max_persistent = -1
pgsql.max_links = -1
pgsql.ignore_notice = 0
pgsql.log_notice = 0
[Sybase-CT]
sybct.allow_persistent = On
sybct.max_persistent = -1
sybct.max_links = -1
sybct.min_server_severity = 10
sybct.min_client_severity = 10
[bcmath]
bcmath.scale = 0
[browscap]
[Session]
session.save_handler = files
session.save_path = "/internalhd/tmp/var/tmp"
session.use_cookies = 1
session.use_only_cookies = 1
session.name = PHPSESSID
session.auto_start = 0
session.cookie_lifetime = 0
session.cookie_path = "/"
session.cookie_domain =
session.cookie_httponly =
session.serialize_handler = php
session.gc_probability = 1
session.gc_divisor = 100
session.gc_maxlifetime = 1440
session.bug_compat_42 = On
session.bug_compat_warn = On
session.referer_check =
session.entropy_length = 16
session.entropy_file = /dev/urandom
session.cache_limiter = private
session.cache_expire = 180
session.use_trans_sid = 0
session.hash_function = 1
session.hash_bits_per_character = 5
url_rewriter.tags = "a=href,area=href,frame=src,input=src,form=fakeentry"
[MSSQL]
mssql.allow_persistent = On
mssql.max_persistent = -1
mssql.max_links = -1
mssql.min_error_severity = 10
mssql.min_message_severity = 10
mssql.compatability_mode = Off
mssql.secure_connection = Off
[Assertion]
[COM]
[mbstring]
[gd]
[exif]
[Tidy]
tidy.clean_output = Off
[soap]
soap.wsdl_cache_enabled=1
soap.wsdl_cache_dir="/internalhd/tmp/var/tmp"
soap.wsdl_cache_ttl=86400
soap.wsdl_cache_limit = 5
[sysvshm]
[ldap]
ldap.max_links = -1
[mcrypt]
[dba]
[apc]
apc.slam_defense=0
');
?>
