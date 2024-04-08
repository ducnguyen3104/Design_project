const validator = new JustValidate('#createSubService');

validator
    .addField('#sub-service-name', [
        {
            rule: "required"
        }
    ])
    .addField('#price', [
        {
            rule: "required"
        },
        {
            rule: "number",
            errorMessage: "Must be a number"
        }
    ])
    .addField('#sub-description', [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        document.getElementById("createSubService").submit();
    })