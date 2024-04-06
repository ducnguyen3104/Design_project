const validator = new JustValidate('#signup');

validator
  .addField('#uname', [
    {
      rule: 'required',
    },
    {
      validator: (value) => () => {
        return fetch("/assignment/account/php/validate-username.php?uname=" + 
                encodeURIComponent(value))
              .then(function(response){
                  return response.json();
              })
              .then(function(json) {
                  return json.available;
              });
      },
      errorMessage: "email   already taken"
    }
  ])
  .addField('#email', [
    {
        rule: 'required'
    },
    {
        rule: 'email'
    },
    {
      validator: (value) => () => {
        return fetch("/assignment/account/php/validate-email.php?email=" + 
                encodeURIComponent(value))
              .then(function(response){
                  return response.json();
              })
              .then(function(json) {
                  return json.available;
              });
      },
      errorMessage: "email   already taken"
    }
  ])
  .addField('#password', [
    {
        rule: 'required'
    },
    {
        rule: "password"
    }
  ])
  .addField('#password_confirmation', [
    {
        validator: (value, fields) => {
            return value === fields["#password"].elem.value;
        },
        errorMessage: "Password should match"
    }
  ])
  .addField('#phone', [
    {
        rule: 'required'
    },
    {
        rule: 'customRegexp',
        value: /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/,
    }
  ])
  .addField('#street_address', [
    {
        rule: 'required'
    }
  ])
  .addField('#city_name', [
    {
        rule: 'required'
    }
  ])
  .addField('#region_name', [
    {
        rule: 'required'
    }
  ])
  .addField('#postal_code_id', [
    {
        rule: 'required'
    }
  ])
  .onSuccess((event) => {
    document.getElementById("signup").submit();
  });