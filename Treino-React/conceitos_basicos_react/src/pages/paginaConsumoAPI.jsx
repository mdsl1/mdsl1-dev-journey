import { useEffect, useState } from "react";
import axios from "axios";
import { Header } from "../components/header";

export function ConsumoAPI() {
    
    const [ tecnologias, setTecnologias ] = useState([]);
    // Por padrão é true pois indica que as informações ainda estão sendo carregadas
    const [carregando, setCarregando] = useState(true);
    const [erro, setErro] = useState(null);

    
    useEffect(() => {
        const carregarDados = async () => {
            try {
                // Limpa os erros anteriores caso tenha antes de tentar
                setErro(null);

                const res = await axios.get("https://api-portfolio-qs3s.onrender.com/tecnologias/show")
                setTecnologias(res.data);
            }
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
            finally {
                // Por fim, indica que a tentativa de carregar os dados finalizou
                setCarregando(false);
            }
        }

        // A chamada pra função fica fora do try/ catch, mas dentro do useEffect
        carregarDados();
    }, []);

    if(carregando) {
        return <p>Coisas boas vindo em sua direção...</p>;
    }

    if(erro) {
        return <p style={{ color: 'red' }}>{ erro }</p>;
    }

    return (
        <div>
            <Header />

            <h1 style={{ marginBottom: "50px"}}>Principais Tecnologias que utilizo:</h1>
            
            <ul style={{ display: "flex", flexFlow: "column nowrap", alignItems: "center", fontSize: "1.3rem" }}>
                { tecnologias?.map((tech) => (
                    <li style={{ marker:"none", marginBottom: "10px"}} key={ tech.id }>{ tech.nome }: { tech.descricao }</li>
                ))}
            </ul>
        </div>
    );
}