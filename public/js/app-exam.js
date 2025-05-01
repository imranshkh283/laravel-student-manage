document.getElementById('start-exam-form').addEventListener('click', function (e) {
    e.preventDefault();

    document.querySelector('#start-exam-form').disabled = true;
    document.getElementById('loading-wrapper').classList.remove('d-none');

    fetch("/admin/load_quiz", {
        method: "GET",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            document.getElementById('loading-wrapper').classList.add('d-none');

            window.location.href = data.redirect;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred, please try again!');
        });
});
