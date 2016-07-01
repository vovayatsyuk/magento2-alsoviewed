<?php

namespace Vovayatsyuk\Alsoviewed\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /**
         * Create table 'alsoviewed_log'
         */
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
            ->setComment('Alsoviewed Log Table');

        $setup->getConnection()->createTable($table);

        /**
         * Create table 'alsoviewed_relation'
         */
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

        $setup->endSetup();
    }
}
