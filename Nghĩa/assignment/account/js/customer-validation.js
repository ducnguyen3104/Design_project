const validator = new JustValidate('#Csignup');

validator
    .addField('#fname', [
        {
            rule: 'customRegexp',
            value: /^[A-Za-z\s'-]+$/,
            errorMessage: "Can not contain number"
        },
        {
            rule: 'required'
        }

    ])
    .addField('#lname', [
        {
            rule: 'customRegexp',
            value: /^[A-Za-z\s'-]+$/,
            errorMessage: "Can not contain number"
        },
        {
            rule: 'required'
        }
    ])
    .onSuccess((event) => {
        document.getElementById("Csignup").submit();
    });