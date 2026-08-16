<?php

// Base model. db() gives a PDO connection once DB_ENABLED is true;
// until then models use sample data.
class Model
{
    protected function db(): ?PDO
    {
        if (!DB_ENABLED) {
            return null;
        }
        return Database::getConnection();
    }
}
