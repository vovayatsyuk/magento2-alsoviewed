<?php

namespace Vovayatsyuk\Alsoviewed\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $this->createLogTable($setup);
        }

        if (version_compare($context->getVersion(), '0.0.3', '<')) {
            $this->dropForeignKeysFromLogTable($setup);
        }

        $setup->endSetup();
    }

    public function createLogTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()
            ->newTable($setup->getTable('alsoviewed_log'))
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Entity ID'
            )
            ->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Product ID'
            )
            ->addColumn(
                'related_product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Related Product ID'
            )
            ->addForeignKey(
                $setup->getFkName('alsoviewed_log', 'product_id', 'catalog_product_entity', 'entity_id'),
                'product_id',
                $setup->getTable('catalog_product_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName('alsoviewed_log', 'related_product_id', 'catalog_product_entity', 'entity_id'),
                'related_product_id',
                $setup->getTable('catalog_product_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Alsoviewed Log Table');

        $setup->getConnection()->createTable($table);
    }

    public function dropForeignKeysFromLogTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable('alsoviewed_log');
        $keys = [
            $setup->getFkName('alsoviewed_log', 'product_id', 'catalog_product_entity', 'entity_id'),
            $setup->getFkName('alsoviewed_log', 'related_product_id', 'catalog_product_entity', 'entity_id')
        ];

        foreach ($keys as $key) {
            $setup->getConnection()->dropForeignKey($table, $key);
            $setup->getConnection()->dropIndex($table, $key);
        }
    }
}
