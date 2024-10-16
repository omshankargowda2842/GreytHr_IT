document.addEventListener('livewire:init', () => {
    const loaderElement = document.getElementById('laravel-livewire-loader');
    let loaderTimeout = null;

    Livewire.hook('request', ({ uri, options, payload, respond, succeed, fail }) => {
        // Show the loader after a delay
        loaderTimeout = setTimeout(() => {
            loaderElement.classList.add('show');
        }, parseInt(loaderElement.dataset.showDelay));

        // Handle the response
        respond(({ status, response }) => {
            // Hide the loader when the response is received
            loaderElement.classList.remove('show');
            clearTimeout(loaderTimeout);
            loaderTimeout = null;
        });

        // Handle successful response
        succeed(({ status, json }) => {
            // Hide the loader when the response is successful
            loaderElement.classList.remove('show');
            clearTimeout(loaderTimeout);
            loaderTimeout = null;
        });

        // Handle failed requests
        fail(({ status, content, preventDefault }) => {
            // Hide the loader in case of failure
            loaderElement.classList.remove('show');
            clearTimeout(loaderTimeout);
            loaderTimeout = null;

            if (status === 419) {
                // Custom handling for session expiration
                confirm('Your session has expired. Would you like to refresh the page?');
                preventDefault(); // Prevent Livewire's default behavior
            } else {
                // Handle other error cases
                alert('An error occurred: ' + content);
            }
        });
    });
});
