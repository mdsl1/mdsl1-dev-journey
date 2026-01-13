import { useSearchParams } from "react-router-dom";
import { Header } from "../components/header";

// Exemplo de uso de manipulação de parametros passados via url por meio da página/ componente "PaginaParams"
export default function PaginaParams () {
    // Como esse hook retorna duas funções, uma para ler os parametros e outra para alterar, é preciso dessestruturar o array, separando as duas funções
    const [ searchParams, setSearchParams ] = useSearchParams();
    
    // Aqui é obtido os valores dos parametros com base na chave setada anteriormente
    const titulo = searchParams.get("titulo");
    const descricao = searchParams.get("descricao");

    // Por fim, retorna a página montada, importando também o Header para navegação
    return (
        <div>
            <Header />
            
            <h1>{ titulo} </h1>
            <p>{ descricao }</p>
        </div>
    );
}