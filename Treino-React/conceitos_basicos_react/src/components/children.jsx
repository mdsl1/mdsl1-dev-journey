// Exemplo de uso de manipulação de elementos chidren por meio do componente "CapsLock_children"
export function CapsLock_children (props) {
    // A função recebe a props, mas acessa o conteúdo interno do elemento, nesse caso o texto
    const texto = props.children;
    const textoCapsLock = texto.toUpperCase();

    // Retorno simples do texto com capslock
    return <div> { textoCapsLock } </div>
}