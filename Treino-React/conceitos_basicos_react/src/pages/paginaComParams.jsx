import { useSearchParams } from "react-router-dom";
import { Header } from "../components/header";

export default function PaginaParams () {
    const [ searchParams ] = useSearchParams();
    
    const titulo = searchParams.get("titulo");
    const descricao = searchParams.get("descricao");

    return (
        <div>
            <Header />
            
            <h1>{ titulo} </h1>
            <p>{ descricao }</p>
        </div>
    );
}