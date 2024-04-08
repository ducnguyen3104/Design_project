const validator = new JustValidate('#Psignup');

validator
    .addField('#Ename', [
        {
            rule: 'required'
        },
        {
            validator: (value) => () => {
              return fetch("/assignment/account/account_php/validate-enterprise.php?Ename=" + 
                      encodeURIComponent(value))
                    .then(function(response){
                        return response.json();
                    })
                    .then(function(json) {
                        return json.available;
                    });
            },
            errorMessage: "Enterprise name already taken"
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
        },
        {
            validator: (value) => () => {
              return fetch("/assignment/account/account_php/validate-siretcode.php?SIRET=" + 
                      encodeURIComponent(value))
                    .then(function(response){
                        return response.json();
                    })
                    .then(function(json) {
                        return json.available;
                    });
            },
            errorMessage: "SIRET code already existed"
          }
    ])
    .onSuccess((event) => {
        document.getElementById("Psignup").submit();
    });