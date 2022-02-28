<?php namespace DocSaver;
include_once (MODX_BASE_PATH . 'assets/lib/MODxAPI/modResource.php');
/**
 * Class Model
 * @package ModSaver
 */
class Model {
    protected $modx = null;

    /**
     * Model constructor.
     * @param \DocumentParser $modx
     */
    public function __construct(\DocumentParser $modx) {
        $this->modx = $modx;
    }

    /**
     * @param string $range
     * @return string
     */
    public function parseRange($range) {
        $doc = new \modResource($this->modx);
        $where = array();
        $items = explode(',', $range);
        $ids = array();
        foreach ($items as $key => $item) {
            $item = trim($item);
            if (is_numeric($item)) {
                $ids[] = $item;
            } elseif (preg_match('/^[\d]+\-[\d]+$/', $item)) {
                $parts = explode('-', $item);
                $where[] = "(c.id >= {$parts[0]} AND c.id <= {$parts[1]})";
            } elseif (preg_match('/^[\d]+\*$/', $item)) {
                $item = rtrim($item, '*');
                $ids[] = $item;
                $where[] = "(c.parent = {$item})";
            } elseif (preg_match('/^[\d]+\*\*$/', $item)) {
                $item = rtrim($item, '*');
                $ids = array_merge($ids, $doc->children($item, true));
            }
        }
        if (!empty($ids)) {
            $ids = implode(',', $ids);
            $where[] = "c.id IN ({$ids})";
        }
        $where = implode(' OR ', $where);

        return $where;
    }

    /**
     * @param string $range
     * @param string $addWhere
     * @return string
     */
    public function buildQuery($range = '', $addWhere='', $lastId = 0) {
        $out = "SELECT c.id FROM {$this->modx->getFullTableName('site_content')} c";
        $where = array();
        $where[] = $this->parseRange($range);
        $where = implode(' OR ', $where);
        $addWhere = trim($addWhere);
        $addWhere .= empty($addWhere) ? 'c.id > ' . $lastId : ' AND c.id > ' . $lastId;
        if (!empty($where) || !empty($addWhere)) {
            $out .= " WHERE ";
        }
        if (!empty($where)) {
            $out .= $where;
            if (!empty($addWhere)) {
                $out .= ' AND ' . $addWhere;
            }
        } elseif (!empty($addWhere)) {
            $out .= $addWhere;
        }

        $out .= ' ORDER BY c.id ASC';

        return $out;
    }

    /**
     * @param string $range
     * @param string $addWhere
     * @return string
     */
    public function buildCountQuery($range = '', $addWhere='') {
        $out = "SELECT COUNT(*) FROM {$this->modx->getFullTableName('site_content')} c";
        $where = array();
        $where[] = $this->parseRange($range);
        $where = implode(' OR ', $where);
        $addWhere = trim($addWhere);
        if (!empty($where) || !empty($addWhere)) {
            $out .= " WHERE ";
        }
        if (!empty($where)) {
            $out .= $where;
            if (!empty($addWhere)) {
                $out .= ' AND ' . $addWhere;
            }
        } elseif (!empty($addWhere)) {
            $out .= $addWhere;
        }

        $out .= ' GROUP BY c.id';
        $out = "SELECT COUNT(*) FROM ({$out}) AS `tmp`";

        return $out;
    }
}
