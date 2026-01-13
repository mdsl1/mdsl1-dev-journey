// Exemplo de uso de manipulação de props por meio do componente "CapsLock_props"
export function CapsLock_props (props) {
    // A função recebe a props e acessa seu conteúdo por meio do nome da propriedade, no caso "texto"    
    const texto = props.texto;
    const textoCapsLock = texto.toUpperCase();
    
    // Retorno simples do texto com capslock
    return <div> { textoCapsLock } </div>
}