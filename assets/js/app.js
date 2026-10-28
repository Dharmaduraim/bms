$(function(){
  $(document).on('submit','.delete-form',function(){ return window.confirm('Delete this record permanently?'); });

  function money(value){ return '₹'+Number(value||0).toFixed(2); }
  function calculateInvoice(){
    var subtotal=0,tax=0;
    $('.invoice-item-row').each(function(){
      var row=$(this),qty=parseFloat(row.find('.item-qty').val())||0,price=parseFloat(row.find('.item-price').val())||0,rate=parseFloat(row.find('.item-tax').val())||0;
      var base=qty*price,taxAmount=base*(rate/100),line=base+taxAmount; subtotal+=base; tax+=taxAmount; row.find('.item-total').text(money(line));
    });
    var discount=Math.max(0,parseFloat($('#invoiceDiscount').val())||0),total=Math.max(0,subtotal+tax-discount);
    $('#sumSubtotal').text(money(subtotal)); $('#sumTax').text(money(tax)); $('#sumTotal').text(money(total));
  }
  $(document).on('input','.item-qty,.item-price,.item-tax,#invoiceDiscount',calculateInvoice);
  $(document).on('change','.product-select',function(){
    var option=$(this).find(':selected'),row=$(this).closest('tr');
    if($(this).val()){ row.find('.item-description').val(option.data('name')); row.find('.item-price').val(option.data('price')); row.find('.item-tax').val(option.data('tax')); calculateInvoice(); }
  });
  $('#addInvoiceItem').on('click',function(){
    var first=$('.invoice-item-row:first'); if(!first.length)return;
    var row=first.clone(); row.find('select').val(''); row.find('.item-description').val(''); row.find('.item-qty').val('1'); row.find('.item-price,.item-tax').val('0'); row.find('.item-total').text('₹0.00'); $('#invoiceItems').append(row); calculateInvoice();
  });
  $(document).on('click','.remove-item',function(){
    if($('.invoice-item-row').length===1){ var row=$(this).closest('tr'); row.find('select').val(''); row.find('input').val(''); row.find('.item-qty').val('1'); row.find('.item-price,.item-tax').val('0'); }
    else $(this).closest('tr').remove(); calculateInvoice();
  });
  calculateInvoice();
});
