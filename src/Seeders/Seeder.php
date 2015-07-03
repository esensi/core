<?php

namespace Esensi\Core\Seeders;

use App\Models\Model;
use Esensi\Core\Contracts\SaveOrFailInterface;
use Esensi\Core\Traits\SaveOrFailTrait;
use Illuminate\Database\Seeder as BaseSeeder;

/**
 * Core Seeder that adds beforeRun and afterRun methods to Laravel's Seeder.
 * Also includes a special saveOrFail() method for showing command line errors.
 *
 * @package Esensi\Core
 * @author daniel <daniel@emersonmedia.com>
 * @author diego <diego@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class Seeder extends BaseSeeder implements SaveOrFailInterface
{
    /**
     * Use the model saving console helper methods.
     *
     * @see Esensi\Core\Traits\SaveOrFailTrait
     */
    use SaveOrFailTrait;

    /**
     * Run before the database seeds.
     *
     * @return void
     */
    public function beforeRun()
    {
        Model::unguard();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('\DatabaseSeeder');
    }

    /**
     * Run after the database seeds.
     *
     * @return void
     */
    public function afterRun()
    {
        Model::reguard();
    }

}
