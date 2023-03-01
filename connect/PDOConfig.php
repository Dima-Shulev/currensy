class PDOConfig
{
    public $DNS;
    public $OPT;
    public $PDO;
    private $config;

    public function __construct()
    {
        $this->config = @json_decode(file_get_contents(dirname(__DIR__)."/config.json"));

        $this->DNS = "mysql:host=" . $this->config->Mysql->host . ";dbname=" . $this->config->Mysql->db . ";charset=" . $this->config->Mysql->charset.";port=".$this->config->Mysql->port."";

        $this->OPT = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        ];

        $this->PDO = new PDO($this->DNS, $this->config->Mysql->user, $this->config->Mysql->pass, $this->OPT);
    }
}
