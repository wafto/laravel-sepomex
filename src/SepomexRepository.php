<?php

namespace Aftab\Sepomex;

use Illuminate\Database\DatabaseManager;

/**
 * Class SepomexRepository
 * @package Aftab\Sepomex
 */
class SepomexRepository
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * SepomexRepository constructor.
     * @param DatabaseManager $database
     */
    public function __construct(DatabaseManager $database)
    {
        $this->table = config('sepomex.table_name');
        $this->database = $database;
    }

    /**
     * Truncate database.
     */
    public function truncate()
    {
        $this->database->connection()->table($this->table())->truncate();
    }

    /**
     * Insert rows as a chunk.
     *
     * @param array $data
     */
    public function insertChunk(array $data)
    {
        $this->database->connection()->table($this->table())->insert($data);
    }

    /**
     * Get table name
     *
     * @return string
     */
    public function table()
    {
        return $this->table;
    }
}
