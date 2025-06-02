
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("#registerForm");
    if (!form || !window.fetch) return;

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const data = {};
        const formData = new FormData(form);

        formData.forEach((value, key) => {
            if (key.endsWith("[]")) {
                key = key.replace("[]", "");
                if (!data[key]) data[key] = [];
                data[key].push(value);
            } else {
                data[key] = value;
            }
        });

        fetch("api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(response => {
            alert("Réponse: " + JSON.stringify(response));
            if (response.profile_url) window.location.href = response.profile_url;
        })
        .catch(err => alert("Erreur: " + err));
    });
});