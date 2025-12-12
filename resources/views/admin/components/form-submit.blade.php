<button type="button" class="btn btn-primary" 
    onclick="event.preventDefault();
    document.getElementById('{{ $formId }}').submit();"
    >{{ $text }}
</button>