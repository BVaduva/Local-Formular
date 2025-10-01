document.querySelectorAll('button[data-redirect]').forEach(button => {
    button.addEventListener('click', function (event) {
        const form = document.getElementById('dynamicForm');
        const redirectUrl = this.getAttribute('data-redirect');

        if (redirectUrl) {
            form.action = redirectUrl; // Set the form action to the button's redirect URL
        }
    });
});
