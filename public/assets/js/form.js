const form = document.getElementById("form");
const bi = document.getElementById("bi");
const nome = document.getElementById("nome");
const genero = document.getElementById("genero");
const data = document.getElementById("data");
const tisangue = document.getElementById("tisangue");
const email = document.getElementById("email");
const contacto = document.getElementById("contacto");
const senha = document.getElementById("senha");
const confSenha = document.getElementById("confSenha");

form.addEventListener("submit", (e) => {
  e.preventDefault();
  checkInputs();
});

function checkInputs() {
  const biValue = bi.value.trim();
  const nomeValue = nome.value.trim();
  const generoValue = genero.value.trim();
  const dataValue = data.value.trim();
  const tisangueValue = tisangue.value;
  const emailValue = email.value.trim();
  const contactoValue = contacto.value.trim();
  const senhaValue = senha.value.trim();
  const confSenhaValue = confSenha.value.trim();

  // Validação do BI
  if (biValue === "") {
    setErrorFor(bi, "O Bilhete de Identidade é obrigatório.");
  } else if (!/^[A-Z0-9]{14}$/.test(biValue)) {
    setErrorFor(bi, "BI inválido (14 caracteres alfanuméricos)");
  } else {
    setSuccessFor(bi);
  }

  // Validação do Nome
  if (nomeValue === "") {
    setErrorFor(nome, "O nome completo é obrigatório.");
  } else {
    setSuccessFor(nome);
  }

  //Validação do Gênero
  if (generoValue === "") {
    setErrorFor(genero, "O gênero é obrigatório.");
    
  } else {
    setSuccessFor(genero);
  }


  const updateDataField = document.getElementById("updateData");
  if (updateDataField) {
    const updateDataValue = updateDataField.value.trim();
    if (updateDataValue === "") {
      setErrorFor(updateDataField, "Atualize a data de nascimento.");
    } else {
      setSuccessFor(updateDataField);
    }
  }
  
  
  //Validação da Data de Nascimento
    if (dataValue === "") {
      setErrorFor(data, "A data de nascimento é obrigatória.");
    } /*else {
      const hoje = new Date();
      const nascimento = new Date(dataValue);
      const idade = Math.floor((hoje - nascimento) / (365.25 * 24 * 60 * 60 * 1000));
      
      if (idade < 18) {
        setErrorFor(data, "Deve ter pelo menos 18 anos");
      } else if (idade > 65) {
        setErrorFor(data, "Idade máxima permitida é 65 anos");
      } else {
        setSuccessFor(data);
      }
    }*/


  // Validação do Tipo Sanguíneo
  if (tisangueValue === "") {
    setErrorFor(tisangue, "Selecione o tipo sanguíneo");
  } else {
    setSuccessFor(tisangue);
  }

  // Validação do Email
  if (emailValue === "") {
    setErrorFor(email, "O email é obrigatório.");
  } else if (!isValidEmail(emailValue)) {
    setErrorFor(email, "Email inválido");
  } else {
    setSuccessFor(email);
  }

  // Validação do Contacto
  if (contactoValue === "") {
    setErrorFor(contacto, "O contacto é obrigatório.");
  } else if (!/^\+244\s?\d{3}\s?\d{3}\s?\d{3}$/.test(contactoValue)) {
    setErrorFor(contacto, "Formato: +244 XXX XXX XXX");
  } else {
    setSuccessFor(contacto);
  }

  // Validação da Senha
  if (senhaValue === "") {
    setErrorFor(senha, "A senha é obrigatória.");
  } else if (senhaValue.length < 8) {
    setErrorFor(senha, "Mínimo 8 caracteres");
  } else {
    setSuccessFor(senha);
  }

  // Confirmação de Senha
  if (confSenhaValue === "") {
    setErrorFor(confSenha, "Confirme a senha");
  } else if (confSenhaValue !== senhaValue) {
    setErrorFor(confSenha, "As senhas não coincidem");
  } else {
    setSuccessFor(confSenha);
  }

  // Verificar validade final
  const formControls = form.querySelectorAll('.form-control');
  const formIsValid = [...formControls].every((control) => {
    return control.classList.contains('success');
  });

  if (formIsValid) {
    form.submit();
  }
}

function setErrorFor(input, message) {
  const formControl = input.parentElement;
  const small = formControl.querySelector('small');
  const errorIcon = formControl.querySelector('.fa-exclamation-circle');
  const successIcon = formControl.querySelector('.fa-check-circle');

  small.innerText = message;
  formControl.className = "form-control error";

}

function setSuccessFor(input) {
  const formControl = input.parentElement;
  const small = formControl.querySelector('small');
  const errorIcon = formControl.querySelector('.fa-exclamation-circle');
  const successIcon = formControl.querySelector('.fa-check-circle');

  formControl.className = "form-control success";

}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// Máscara para contacto
contacto.addEventListener('input', function(e) {
  let x = e.target.value.replace(/\D/g, '').substring(0, 12);
  let formatted = '+244';
  
  if (x.length > 3) {
    formatted += ' ' + x.substring(3, 6);
  }
  if (x.length > 6) {
    formatted += ' ' + x.substring(6, 9);
  }
  if (x.length > 9) {
    formatted += ' ' + x.substring(9, 12);
  }
  
  e.target.value = formatted;
});