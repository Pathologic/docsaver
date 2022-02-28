<?php namespace DocSaver;

include_once('model.php');

/**
 * Class Controller
 */
class ModuleController
{
    protected $modx = null;
    protected $data = null;
    public $isExit = false;
    public $output = null;

    /**
     * Controller constructor.
     * @param \DocumentParser $modx
     */
    public function __construct(\DocumentParser $modx)
    {
        $this->modx = $modx;
        $this->data = new Model($modx);
    }

    /**
     *
     */
    public function callExit()
    {
        if ($this->isExit) {
            echo $this->output;
            exit;
        }
    }

    /**
     * @return array
     */
    public function start()
    {
        $_SESSION['DocSaver']['RecordsProcessed'] = 0;
        $_SESSION['DocSaver']['RecordsTotal'] = 0;
        $_SESSION['DocSaver']['LastId'] = 0;
        $range = isset($_POST['range']) && is_scalar($_POST['range']) ? $_POST['range'] : '';
        $addWhere = isset($_POST['addWhere']) && is_scalar($_POST['addWhere']) ? $_POST['addWhere'] : '';
        $sql = $this->data->buildCountQuery($range, $addWhere);
        $q = $this->modx->db->query($sql);
        $_SESSION['DocSaver']['RecordsTotal'] = $total = $this->modx->db->getValue($q);
        $out = array('success' => true, 'message' => $total);

        return $out;
    }

    /**
     * @return array
     */
    public function process()
    {
        $lastId = &$_SESSION['DocSaver']['LastId'];
        $_time = microtime(true);
        $range = isset($_POST['range']) && is_scalar($_POST['range']) ? $_POST['range'] : '';
        $addWhere = isset($_POST['addWhere']) && is_scalar($_POST['addWhere']) ? $_POST['addWhere'] : '';
        $sql = $this->data->buildQuery($range, $addWhere, $lastId);
        $q = $this->modx->db->query($sql);
        while ($id = $this->modx->db->getValue($q)) {
            if ($id) $this->modx->invokeEvent('OnDocFormSave', array('id' => $id, 'mode' => 'upd'));
            $lastId = $id;
            $_SESSION['DocSaver']['RecordsProcessed']++;
            $time = microtime(true) - $_time;
            if ($time > 5) {
                break;
            }
        }

        return $this->getStatus();
    }

    /**
     * @return array
     */
    protected function getStatus()
    {
        $out = array();

        $out['success'] = true;
        $out['total'] = $_SESSION['DocSaver']['RecordsTotal'];
        if (!isset($_SESSION['DocSaver']['RecordsProcessed'])) {
            $out['processed'] = 0;
        } else {
            $out['processed'] = $_SESSION['DocSaver']['RecordsProcessed'] < $_SESSION['DocSaver']['RecordsTotal'] ? $_SESSION['DocSaver']['RecordsProcessed'] : $_SESSION['DocSaver']['RecordsTotal'];
        }

        return $out;
    }
}
