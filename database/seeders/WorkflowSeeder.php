<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Temporal\Common\Uuid;
use Illuminate\Support\Facades\DB;

class WorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('workflows')->insert([
            'uuid' => Uuid::v4(),
            'version' => '1.0',
            'spec_version' => '1.0',
            'name' => 'HelloWorkflow',
            'friendly_name' => 'Hello Workflow',
            'hash' => md5('HelloWorkflow'),
            'publish_status' => 'published',
            'has_dsl' => false,
            'classpath' => 'Boomtown\Implementations\HelloWorkflow',
            'dsl' => json_encode([]),
            'is_callable' => false,
            'created_by' => '',
            'created_at' => DB::raw('NOW()')
        ]);
    }
}
