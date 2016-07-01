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

        if (version_compare($context->getVersion(), '0.1.0', '<')) {
            $this->createRelationTable($setup);
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

    public function createRelationTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()
            ->newTable($setup->getTable('alsoviewed_relation'))
            ->addColumn(
                'relation_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'unsigned' => true, 'primary' => true],
                'Relation ID'
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
            ->addColumn(
                'weight',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default'  => 0],
                'Relation Weight'
            )
            ->addColumn(
                'position',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default'  => 50],
                'Custom Sort Order'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default'  => 1],
                'Status'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Update Date'
            )
            ->addIndex(
                $setup->getIdxName(
                    'alsoviewed_relation',
                    ['product_id', 'related_product_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['product_id', 'related_product_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName('alsoviewed_relation', ['product_id']),
                ['product_id']
            )
            ->addIndex(
                $setup->getIdxName('alsoviewed_relation', ['weight', 'position']),
                ['weight', 'position']
            )
            ->addIndex(
                $setup->getIdxName('alsoviewed_relation', ['status']),
                ['status']
            )
            ->addIndex(
                $setup->getIdxName('alsoviewed_relation', ['updated_at']),
                ['updated_at']
            )
            ->addForeignKey(
                $setup->getFkName('alsoviewed_relation', 'product_id', 'catalog_product_entity', 'entity_id'),
                'product_id',
                $setup->getTable('catalog_product_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName('alsoviewed_relation', 'related_product_id', 'catalog_product_entity', 'entity_id'),
                'related_product_id',
                $setup->getTable('catalog_product_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Alsoviewed Log Table');

        $setup->getConnection()->createTable($table);
    }
}
