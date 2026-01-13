import { useEffect, useState } from "react";
import axios from "axios";
import { Header } from "../components/header";

// Exemplo de manipulação de APIs por meio da página/ componente "ConsumoAPI"
export default function ConsumoAPI() {
    
    // Variável de estado contendo o array onde será inserido os valores da API, assim como seu setter
    const [ tecnologias, setTecnologias ] = useState([]);
    // Por padrão é true pois indica que as informações ainda estão sendo carregadas
    const [carregando, setCarregando] = useState(true);
    const [erro, setErro] = useState(null);

    // useEffect é usado sempre que for interagir com algo fora do fluxo normal de um projeto react, como nesse caso, onde vamos consultar uma API
    useEffect(() => {
        // Função assincrona para carregar os dados
        const carregarDados = async () => {
            try {
                // Limpa os erros anteriores caso tenha antes de tentar
                setErro(null);

                // Consulta a API via axios, e armazena os valores na variável de estado
                const res = await axios.get("https://api-portfolio-qs3s.onrender.com/tecnologias/show")
                setTecnologias(res.data);
            }
            // Captura e interpreta os erros
            catch(error) {
                console.error(error);
                // Uso do "?" (Optional Chaining) para não quebrar se response for undefined
                if(error.response?.status === 404) {
                    setErro("Dados não encontrados.");
                }
                else {
                    setErro("Falha ao carregar dados. Por segurança, tente novamente.");
                }
            }
            // Esse finally é usado tanto ao fim do try quanto do catch, por isso é bom usar para alterar a variável de estado "carregando", indicando que o consumo da API chegou ao fim
            finally {
                // Por fim, indica que a tentativa de carregar os dados finalizou
                setCarregando(false);
            }
        }

        // A chamada pra função fica fora do try/ catch, mas dentro do useEffect
        carregarDados();
    }, []); // Esse array ao fim do useEffect é importante, pois ele define quando o useEffect será executado. Sendo:
            // - [] vazio → executa apenas uma vez (quando o componente monta).
            // - [variavel] → executa sempre que variavel mudar.
            // - Sem array → executa em toda renderização.

    // Se a página estiver carregando, retorna um aviso de carregamento
    if(carregando) {
        return <p>Coisas boas vindo em sua direção...</p>;
    }

    // Se houver algum erro no retorno da API, retorna um aviso de erro
    if(erro) {
        return <p style={{ color: 'red' }}>{ erro }</p>;
    }

    // Caso tudo tenha dado certo, retorna os valores vindos da API
    return (
        <div>
            <Header />

            <h1 style={{ marginBottom: "50px"}}>Principais Tecnologias que utilizo:</h1>
            
            <ul style={{ display: "flex", flexFlow: "column nowrap", alignItems: "center", fontSize: "1.3rem" }}>
                {   // É usado o map para percorrer o array e gerar um "li" com cada valor 
                tecnologias?.map((tech) => (
                    <li style={{ marker:"none", marginBottom: "10px"}} key={ tech.id }>{ tech.nome }: { tech.descricao }</li>
                ))}
            </ul>
        </div>
    );
}