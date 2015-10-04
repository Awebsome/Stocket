<?php

return [
    'plugin' => [
        'name' => 'Stock',
        'description' => 'Plugin de comercio para October, que le permite configurar una tienda local y en línea con facilidad.'
    ],

    'sales' => [
        'menu_label'    => 'Ventas',
        'product'       => 'Producto',
        'sale'          => 'Venta',
        'sales'         => 'Ventas',
        'preview_sales' => 'Ventas',
        'manage_sales'  => 'Gestión de Ventas',
        'new_sale'      => 'Nueva Venta',
        'edit_sale'     => 'Editar Venta',
        'checkout'      => 'Checkout',
        'recalculate'   => 'Recalcular',

        'customer'      => 'Vendedor',
        'current_customer'=> 'Vendedor Actual',
        'meter'         => 'Meta del Mes',
        'month_meter'   => 'Meta del Mes',
        'covered_expenses'   => 'Gastos cubiertos',
        'profit'        => 'Ingresos',
        'expense'       => 'Gastos',
        'current_month' => 'Mes Corriente',
        'confirm_closed_sale' => '¿Desea confirmar esta venta?',
        'sale_is_closed' => 'Esta venta ya ha sido confirmada!',

        'title'         => 'Titulo',
        'quantity'      => 'Cantidad',
        'price'         => 'Precio',
        'expenses'      => 'Gastos',
        'subtotal'      => 'Subtotal',
        'discount'      => 'Descuento',
        'total'         => 'Total',
        'total_sales'   => 'Ventas Totales',
        'cash'          => 'Efectivo',
        'credit_card'   => 'Tarjeta',
        'current_account' => 'CTE',

        'please_opening_cash_register' => 'Usted aún no ha hecho la apertura de caja, para realizar ventas.',
        'sale_recalculate' => 'Factura recalculada exitosamente!',
        'sale_successfully' => 'Venta realizada con éxito!',
        'return_to_manage_sales' => 'Volver a gestión de ventas',
    ],

    'tills' => [
        'menu_label'    => 'Caja',
        'manage_tills'    => 'Administrar Caja',
        'operation'    => 'Operación',
        'new_operation'    => 'Nueva Operación',
        'edit_operation'    => 'Editar Operación',
        'preview_operation'    => 'Operaciones',
        'action'    => 'Operación',
        'description'    => 'Descripción',
        'in_till'    => 'En Caja',

        'created_at'    => 'Fecha y Hora',

        /**
         * No modificar varname
         * use tills for operations.
         */
        'opening_till'  => 'Apertura de caja',
        'closing_till'  => 'Cierre de caja',
        'deposit'       => 'Ingreso de Dinero',
        'sale'          => 'Nueva Venta',
        'withdrawal'    => 'Retiro de Dinero',

        'deposit_withdrawl'     => 'Ingreso / Retiro',
        'confirm_opening'       => '¿Desea realizar la apertura de caja?',
        'confirm_closed'       => '¿Desea realizar el cierre de caja correspondiente?',
        'opening_successfully'  => 'Apertuda de caja realizada con éxito.',
        'already_opening'       => 'Ya haz realizado la apertura de caja o aún tienes un cierre pendiente.',
        'closed_successfully'       => 'Cierra de caja realizado con éxito.',
        'already_closed'        => 'Ya haz realizado el cierre de caja o aún no haz hecho una apertura.',
    ],

    'invoice' => [
        'name'          => 'Nombre',
        'fullname'      => 'Nombre Completo',
        'invoice'       => 'Factura',
        'invoice_number' => 'Numero de Factura',
        'invoicing'     => 'Facturación',
        'description'   => 'Descripción',
        'phone'         => 'Telefono',
        'payment'       => 'Forma de pago',
        'status'        => 'Estado',
        'price'         => 'Precio',

        'address'       => 'Dirección',
        'city'          => 'Ciudad',
        'state'         => 'Provincia',
        'country'       => 'Pais',

        'cash'          => 'Efectivo',
        'credit_card'   => 'Tarjeta',
        'current_account' => 'CTE',
    ],

    'products' => [
        'menu_label'    => 'Productos',
        'new_product'   => 'Nuevo Producto',

        'product'       => 'Producto',
        'products'      => 'Productos',
        'new_product'   => 'Nuevo Producto',
        'edit_product'  => 'Editar Producto',
        'manage_product' => 'Gestión de Producto',
        'preview_product' => 'Gestión de Producto',

        'title' => 'Nombre',
        'model' => 'Modelo',
        'product_name' => 'Nombre del producto',
        'is_enabled' => 'Vista Online',
        'is_stockable' => 'Stock',
        'images' => 'Imagenes',
    ],

    'categories' => [
        'menu_label'    => 'Categorias',
        'category'      => 'Categoria',
    ],

    'expenses' => [
        'menu_label'    => 'Gastos',
        'expenses'      => 'Gastos',
        'expense'       => 'Gasto',
        'new_expense'   => 'Nuevo Gasto',
        'edit_expense'  => 'Editar Gasto',
        'manage_expenses'  => 'Gastos',

        'title'         => 'Titulo',
        'description'   => 'Descripción',
        'amount'        => 'Monto',
        'expiration'    => 'Vencimiento',
        'status'        => 'Estado de pago',
        'paid'          => 'Pago Realizado',
        'paid_pending'  => 'Pago Pendiente',
    ],
];