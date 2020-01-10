<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface DeletePrepHooks
{
    /**
     * Processing before the model is written after the transaction
     * @return mixed
     */
    public function __deletePrepHooks();
}
