const validator = new JustValidate('#createService');

validator
    .addField('#service-name', [
        {
            rule: "required"
        }
    ])
    .addField('#description', [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        document.getElementById("createService").submit();
    })