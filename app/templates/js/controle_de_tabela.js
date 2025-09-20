const anos = document.querySelectorAll(".linha_ano");

anos.forEach((ano) => {
    ano.addEventListener("click", () => {
        const meses = document.querySelectorAll(".linha_mes");

        meses.forEach((mes) => {
            if(mes.classList.contains(ano.innerText))
            {
                if(mes.ariaHidden == 'true')
                {
                    mes.ariaHidden = 'false';
                }
                else
                {
                    mes.ariaHidden = 'true';
                }

                mes.addEventListener("click", () => {
                    const despesas = document.querySelectorAll('.linha_lancamento');

                    despesas.forEach((despesa) => {
                        if(despesa.classList.contains(ano.innerText+'-'+mes.innerText))
                        {
                            if(despesa.ariaHidden == 'true')
                            {
                                despesa.ariaHidden = 'false';
                            }
                            else
                            {
                                despesa.ariaHidden = 'true';
                            }
                        }
                    });
                });
            }
        });
    });
});


const anos_rec = document.querySelectorAll(".linha_ano_rec");

anos_rec.forEach((ano) => {
    ano.addEventListener("click", () => {
        const meses = document.querySelectorAll(".linha_mes_rec");

        meses.forEach((mes) => {
            if(mes.classList.contains(ano.innerText))
            {
                if(mes.ariaHidden == 'true')
                {
                    mes.ariaHidden = 'false';
                }
                else
                {
                    mes.ariaHidden = 'true';
                }

                mes.addEventListener("click", () => {
                    const receitas = document.querySelectorAll('.linha_lancamento_rec');

                    receitas.forEach((receita) => {
                        if(receita.classList.contains(ano.innerText+'-'+mes.innerText))
                        {
                            if(receita.ariaHidden == 'true')
                            {
                                receita.ariaHidden = 'false';
                            }
                            else
                            {
                                receita.ariaHidden = 'true';
                            }
                        }
                    });
                });
            }
        });
    });
});