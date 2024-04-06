const validator = new JustValidate('#Psignup');

validator
    .addField('#Ename', [
        {
            rule: 'required'
        }
    ])
    .addField('#SIRET', [
        {
            rule: 'customRegexp',
            value: /^(\d{3}[-\s]?){3}\d{5}$/,
            errorMessage: "Require 14 numbers"
        },
        {
            rule: 'required'
        }
    ])
    .onSuccess((event) => {
        document.getElementById("Psignup").submit();
    });