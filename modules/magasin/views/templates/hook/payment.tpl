{if isset($cart->id_carrier) && $cart->id_carrier == $carrier_magasin}

   <div class="row">

<div class="col-xs-12 col-md-6">

<p class="payment_module">
	<img src="{$this_path_magasin}magasin.png" alt="{l s='Payer au magasin.' mod='magasin'}" width="60" height="60" style="margin-bottom: 10px;"/>
	<a href="{$link->getModuleLink('magasin', 'payment', [], true)|escape:'html'}" title="{l s='Payer au magasin.' mod='magasin'}">

		<span style="font-size: 22px; margin: 10px 0px; line-height: 27px; font-family: 'Montserrat', sans-serif;">{l s='Payer au restaurant' mod='magasin'}</span>
	 	<span style="font-size: 16px; font-family: 'Pacifico'; color: #999; font-style: italic; text-transform: lowercase;">Vous pourrez payer et retirer votre commande au restaurant ></span>

	</a>

</p>

</div>

</div>

{/if}