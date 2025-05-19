// form.js

document.addEventListener('DOMContentLoaded', () => {
  const form       = document.getElementById('form');
  const bi         = document.getElementById('bi');
  const nome       = document.getElementById('nome');
  const genero     = document.getElementById('genero');
  const dataField  = document.getElementById('data');
  const getHidden  = () => document.getElementById('dataHidden');
  const tisangue   = document.getElementById('tisangue');
  const email      = document.getElementById('email');
  const contacto   = document.getElementById('contacto');
  const senha      = document.getElementById('senha');
  const confSenha  = document.getElementById('confSenha');

  // URLs injetadas no Blade
  const urlCheckBI    = form.dataset.checkBiUrl;
  const urlCheckEmail = form.dataset.checkEmailUrl;
  const csrfToken     = document.querySelector('meta[name="csrf-token"]').content;

  // Regex de formato
  const regexBI    = /^[A-Za-z0-9]{14}$/;
  const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const regexTel   = /^\+244\s?9\d{2}\s?\d{3}\s?\d{3}$/;

  // Helpers
  function setError(el, msg) {
    const ctrl = el.closest('.form-control, .data');
    ctrl.classList.add('error');
    ctrl.classList.remove('success');
    ctrl.querySelector('.error-message').innerText = msg;
  }
  function setSuccess(el) {
    const ctrl = el.closest('.form-control, .data');
    ctrl.classList.remove('error');
    ctrl.classList.add('success');
    ctrl.querySelector('.error-message').innerText = '';
  }

  // Síncrono
  function validateSync() {
    let ok = true;

    // BI
    const biVal = bi.value.trim();
    if (!biVal) {
      setError(bi, 'BI é obrigatório'); ok = false;
    } else if (!regexBI.test(biVal)) {
      setError(bi, 'BI deve ter 14 caracteres alfanuméricos'); ok = false;
    } else {
      setSuccess(bi);
    }

    // Nome
    const nomeVal = nome.value.trim();
    if (!nomeVal) {
      setError(nome, 'Nome é obrigatório'); ok = false;
    } else if (nomeVal.length < 5) {
      setError(nome, 'Nome deve ter ≥5 caracteres'); ok = false;
    } else {
      setSuccess(nome);
    }

    // Gênero
    if (!genero.value) {
      setError(genero, 'Selecione o gênero'); ok = false;
    } else {
      setSuccess(genero);
    }

    // Data / Idade
    const upd   = document.getElementById('updateData');
    let dateStr = '';
    if (upd) {
      // Campo de atualização injetado
      // Exibe instrução no campo original
      setError(dataField, 'Por favor, atualize a data abaixo');
      if (!upd.value) {
        setError(upd, 'Informe sua data de nascimento'); ok = false;
      } else {
        dateStr = upd.value;
      }
    } else {
      // Data original
      const dh = getHidden();
      if (!dh || !dh.value) {
        setError(dataField, 'Data é obrigatória'); ok = false;
      } else {
        dateStr = dh.value;
      }
    }
    // Se dateStr presente, calcula idade
    if (dateStr) {
      const [y,m,d] = dateStr.split('-').map(Number);
      const birth   = new Date(y, m-1, d);
      const today   = new Date();
      let age       = today.getFullYear() - birth.getFullYear();
      const md      = today.getMonth() - birth.getMonth();
      if (md < 0 || (md === 0 && today.getDate() < birth.getDate())) age--;
      if (age < 18 || age > 65) {
        const target = upd ? upd : dataField;
        setError(target, 'Idade deve estar entre 18 e 65 anos'); ok = false;
      } else {
        if (upd) setSuccess(upd);
        else    setSuccess(dataField);
      }
    }

    // Tipo sanguíneo
    if (!tisangue.value) {
      setError(tisangue, 'Selecione o tipo sanguíneo'); ok = false;
    } else {
      setSuccess(tisangue);
    }

    // E‑mail
    const emailVal = email.value.trim();
    if (!emailVal) {
      setError(email, 'E‑mail é obrigatório'); ok = false;
    } else if (!regexEmail.test(emailVal)) {
      setError(email, 'Formato de e‑mail inválido'); ok = false;
    } else {
      setSuccess(email);
    }

    // Contacto
    const contVal = contacto.value.trim();
    if (!contVal) {
      setError(contacto, 'Contacto é obrigatório'); ok = false;
    } else if (!regexTel.test(contVal)) {
      setError(contacto, 'Formato: +244 9XX XXX XXX'); ok = false;
    } else {
      setSuccess(contacto);
    }

    // Senha
    const senVal = senha.value;
    if (!senVal) {
      setError(senha, 'Senha é obrigatória'); ok = false;
    } else if (senVal.length < 8) {
      setError(senha, 'Senha deve ter ≥8 caracteres'); ok = false;
    } else {
      setSuccess(senha);
    }

    // Confirmação
    const confVal = confSenha.value;
    if (!confVal) {
      setError(confSenha, 'Confirme a senha'); ok = false;
    } else if (confVal !== senVal) {
      setError(confSenha, 'Senhas não coincidem'); ok = false;
    } else {
      setSuccess(confSenha);
    }

    return ok;
  }

  // Assíncrono
  async function validateAsync() {
    let ok = true;

    // BI único
    const biVal = bi.value.trim();
    if (regexBI.test(biVal)) {
      try {
        const res = await fetch(urlCheckBI, {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Accept':'application/json',
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':csrfToken
          },
          body: JSON.stringify({ numero_bilhete: biVal })
        });
        if (!res.ok) {
          setError(bi, 'Erro ao verificar BI'); ok = false;
        } else {
          const json = await res.json();
          if (json.exists) {
            setError(bi, 'Este BI já está cadastrado'); ok = false;
          } else {
            setSuccess(bi);
          }
        }
      } catch {
        setError(bi, 'Falha de conexão ao verificar BI'); ok = false;
      }
    }

    // E‑mail único
    const emailVal = email.value.trim();
    if (regexEmail.test(emailVal)) {
      try {
        const res = await fetch(urlCheckEmail, {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Accept':'application/json',
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':csrfToken
          },
          body: JSON.stringify({ email: emailVal })
        });
        if (!res.ok) {
          setError(email, 'Erro ao verificar e‑mail'); ok = false;
        } else {
          const json = await res.json();
          if (json.exists) {
            setError(email, 'Este e‑mail já está registrado'); ok = false;
          } else {
            setSuccess(email);
          }
        }
      } catch {
        setError(email, 'Falha de conexão ao verificar e‑mail'); ok = false;
      }
    }

    return ok;
  }

  // Submissão
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    document.querySelectorAll('.form-control, .data').forEach(ctrl => {
      ctrl.classList.remove('error','success');
      ctrl.querySelector('.error-message').innerText = '';
    });
    const okSync  = validateSync();
    const okAsync = await validateAsync();
    if (okSync && okAsync) form.submit();
  });

  // Sincroniza updateData → hidden e limpa erro
  document.addEventListener('change', e => {
    if (e.target.id === 'updateData') {
      const dh = getHidden();
      if (dh) dh.value = e.target.value;
      setSuccess(dataField);
    }
  });
});
