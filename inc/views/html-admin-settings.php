<?php
/**
 * Settings for MCFE
 *
 * @package inc/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- Custom Table -->

<table class="wc-shipping-zone-methods wc-ecfe-table widefat">
    <thead>
        <tr>
            <th class="wc-shipping-zone-method-sort"></th>
            <th class="wc-shipping-zone-method-title"><?php esc_html_e( 'Name', 'woocommerce' ); ?></th>
            <th class="wc-shipping-zone-method-label"><?php esc_html_e( 'Label', 'woocommerce' ); ?></th>
            <th class="wc-shipping-zone-method-placeholder"><?php esc_html_e( 'Placeholder', 'woocommerce' ); ?></th>
            <th class="wc-shipping-zone-method-validations"><?php esc_html_e( 'Validations', 'woocommerce' ); ?></th>
            <th class="wc-shipping-zone-method-requred"><?php esc_html_e( 'Required', 'woocommerce' ); ?></th>
            <th class="wc-shipping-zone-method-enabled"><?php esc_html_e( 'Enabled', 'woocommerce' ); ?></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="2">
                <button type="submit" class="button wc-shipping-zone-add-method"
                    value="<?php esc_attr_e( 'Add shipping method', 'woocommerce' ); ?>"><?php esc_html_e( 'Add shipping method', 'woocommerce' ); ?></button>
            </td>

            <td colspan="1">
                <button type="submit" class="button wc-ecfe-set-default"
                    value="<?php esc_attr_e( 'Set Default', 'woocommerce' ); ?>"><?php esc_html_e( 'Set Default', 'woocommerce' ); ?></button>
            </td>

            <td colspan="4">
            </td>

        </tr>
    </tfoot>
    <tbody class="wc-shipping-zone-method-rows wc-ecfe-rows"></tbody>
</table>

<?php do_action( 'woocommerce_shipping_zone_after_methods_table', $zone ); ?>

<p class="submit">
	<button type="submit" name="submit" id="submit" class="button button-primary button-large wc-shipping-zone-method-save wc-ecfe-save" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
</p>


<script type="text/html" id="wc-ecfe-row-template-blank">
<tr>
    <td class="wc-shipping-zone-method-blank-state" colspan="4">
        <p><?php esc_html_e( 'You can add multiple shipping methods within this zone. Only customers within the zone will see them.', 'woocommerce' ); ?>
        </p>
    </td>
</tr>
</script>

<script type="text/html" id="wc-ecfe-row-template">
<tr data-id="" data-enabled="">
    <td class="wc-shipping-zone-method-sort"></td>
    <td class="wc-shipping-zone-method-title">
    <%= key %>
    <div class="row-actions">
				<a class="wc-shipping-zone-method-settings" data-name="<%= value.name %>" href="admin.php?page=wc-settings&amp;tab=checkout_settings&amp;name=<%= value.name %>"><?php esc_html_e( 'Edit', 'woocommerce' ); ?></a> | <a href="#" class="wc-shipping-zone-method-delete"><?php esc_html_e( 'Delete', 'woocommerce' ); ?></a>
	</div>
    </td>

    <td>
    <%= value.label %>
    </td>

    <td>
    <%= value.placeholder %>
    </td>

    <td>
    <%= value.validate %>
    </td>

    <td>
    <%= value.required %>
    </td>

    <td>
    <%= value.enabled %>
    </td>
</tr>
</script>

<script type="text/template" id="tmpl-wc-modal-shipping-method-settings">
    <div class="wc-backbone-modal wc-backbone-modal-shipping-method-settings">
		<div class="wc-backbone-modal-content">
			<section class="wc-backbone-modal-main" role="main">
				<header class="wc-backbone-modal-header">
					<h1>
						<?php
						printf(
							/* translators: %s: shipping method title */
							esc_html__( '%s Settings', 'woocommerce' ),
							''
						);
						?>
					</h1>
					<button class="modal-close modal-close-link dashicons dashicons-no-alt">
						<span class="screen-reader-text"><?php esc_html_e( 'Close modal panel', 'woocommerce' ); ?></span>
					</button>
				</header>
				<article class="wc-modal-shipping-method-settings">
					<form action="" method="post">
						{{{ console.log( data ) }}}
						{{{ data.setting_html }}}

					</form>
				</article>
				<footer>
					<div class="inner">
						<button id="btn-ok" class="button button-primary button-large"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
					</div>
				</footer>
			</section>
		</div>
	</div>
	<div class="wc-backbone-modal-backdrop modal-close"></div>
</script>

<script type="text/template" id="tmpl-wc-modal-add-shipping-method">
    <div class="wc-backbone-modal">
		<div class="wc-backbone-modal-content">
			<section class="wc-backbone-modal-main" role="main">
				<header class="wc-backbone-modal-header">
					<h1><?php esc_html_e( 'Add shipping method', 'woocommerce' ); ?></h1>
					<button class="modal-close modal-close-link dashicons dashicons-no-alt">
						<span class="screen-reader-text"><?php esc_html_e( 'Close modal panel', 'woocommerce' ); ?></span>
					</button>
				</header>
				<article>
					<form action="" method="post">
						<div class="wc-shipping-zone-method-selector">
							<p><?php esc_html_e( 'Choose the shipping method you wish to add. Only shipping methods which support zones are listed.', 'woocommerce' ); ?></p>

							<select name="add_method_id">
								<?php
								foreach ( WC()->shipping()->load_shipping_methods() as $method ) {
									if ( ! $method->supports( 'shipping-zones' ) ) {
										continue;
									}
									echo '<option data-description="' . esc_attr( wp_kses_post( wpautop( $method->get_method_description() ) ) ) . '" value="' . esc_attr( $method->id ) . '">' . esc_attr( $method->get_method_title() ) . '</li>';
								}
								?>
							</select>
						</div>
					</form>
				</article>
				<footer>
					<div class="inner">
						<button id="btn-ok" class="button button-primary button-large"><?php esc_html_e( 'Add shipping method', 'woocommerce' ); ?></button>
					</div>
				</footer>
			</section>
		</div>
	</div>
	<div class="wc-backbone-modal-backdrop modal-close"></div>
</script>

<style>
.woocommerce-save-button:not(.wc-ecfe-save){
    display:none
}
</style>