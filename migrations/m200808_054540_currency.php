<?php

use yii\db\Migration;

/**
 * Class m200808_054540_currency
 */
class m200808_054540_currency extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('currency', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->unique()->notNull(),
            'rate' => $this->float()->notNull(),
            'insert_dt' => $this->integer(11)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('currency');
    }
}
