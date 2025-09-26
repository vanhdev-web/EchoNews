<?php

namespace App\Core;

use Database\DataBase;

/**
 * Base Model Class
 * Cung cấp functionality cơ bản cho tất cả models
 */
abstract class BaseModel
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $timestamps = true;
    
    public function __construct()
    {
        $this->db = new DataBase();
    }
    
    /**
     * Tìm record theo ID
     */
    public function find($id)
    {
        $result = $this->db->select(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?", 
            [$id]
        );
        return $result ? $result->fetch() : null;
    }
    
    /**
     * Tìm tất cả records
     */
    public function all($orderBy = 'created_at', $order = 'DESC')
    {
        $result = $this->db->select(
            "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}"
        );
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Tìm records với điều kiện WHERE
     */
    public function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $result = $this->db->select(
            "SELECT * FROM {$this->table} WHERE {$column} {$operator} ?", 
            [$value]
        );
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Tìm record đầu tiên với điều kiện
     */
    public function first($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $result = $this->db->select(
            "SELECT * FROM {$this->table} WHERE {$column} {$operator} ? LIMIT 1", 
            [$value]
        );
        return $result ? $result->fetch() : null;
    }
    
    /**
     * Tạo record mới
     */
    public function create($data)
    {
        $fillableData = $this->filterFillable($data);
        $fields = array_keys($fillableData);
        $values = array_values($fillableData);
        
        return $this->db->insert($this->table, $fields, $values);
    }
    
    /**
     * Cập nhật record
     */
    public function update($id, $data)
    {
        $fillableData = $this->filterFillable($data);
        $fields = array_keys($fillableData);
        $values = array_values($fillableData);
        
        return $this->db->update($this->table, $id, $fields, $values);
    }
    
    /**
     * Xóa record
     */
    public function delete($id)
    {
        return $this->db->delete($this->table, $id);
    }
    
    /**
     * Đếm số records
     */
    public function count($where = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $params = [];
        
        if ($where) {
            $sql .= " WHERE {$where['column']} {$where['operator']} ?";
            $params[] = $where['value'];
        }
        
        $result = $this->db->select($sql, $params);
        return $result ? $result->fetch()['count'] : 0;
    }
    
    /**
     * Pagination
     */
    public function paginate($page = 1, $perPage = 10, $where = null)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if ($where) {
            $sql .= " WHERE {$where['column']} {$where['operator']} ?";
            $params[] = $where['value'];
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}";
        
        $result = $this->db->select($sql, $params);
        return $result ? $result->fetchAll() : [];
    }
    
    /**
     * Lọc chỉ các field fillable
     */
    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    /**
     * Ẩn các field sensitive
     */
    protected function hideAttributes($data)
    {
        if (empty($this->hidden)) {
            return $data;
        }
        
        return array_diff_key($data, array_flip($this->hidden));
    }
    
    /**
     * Custom query
     */
    public function query($sql, $params = [])
    {
        return $this->db->select($sql, $params);
    }
}