@section('')
@endsection

@push('test')
    $(document).on('click', '.deleteItem', function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Prevent multiple executions
        if (isDeleting) {
            return false;
        }
        isDeleting = true;
        
        // get the total of the item in the same row from the hidden input
        let itemTotal = parseFloat($(this).closest('tr').find('input[name*="[itemTotal]"]').val()) || 0;
        console.log('Deleting item with total:', itemTotal);
        console.log('Total price before deletion:', totalPrice);
        
        totalPrice -= itemTotal;
        totalPrice = Math.round((totalPrice + Number.EPSILON) * 100) / 100;
        console.log('Total price after deletion:', totalPrice);
        
        $("#total_price").text(totalPrice);
        calculateDiscount();
        $(this).closest('tr').remove();
        counter--; // Decrement counter when deleting
        
        // Reset flag after a short delay
        setTimeout(function() {
            isDeleting = false;
        }, 100);
    })
@endpush