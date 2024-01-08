function debounce(func, delay) {
    let timeout;
    return function() {
        const context = this;
        const args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            func.apply(context, args);
        }, delay);
    };
}

function setupDebouncer(inputElement, formElement) {
    inputElement.addEventListener("input", debounce(function() {
        formElement.submit();
    }, 550));
}