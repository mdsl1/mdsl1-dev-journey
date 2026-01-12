export function CapsLock_children (props) {
    const texto = props.children;
    const textoCapsLock = texto.toUpperCase();

    return <div> { textoCapsLock } </div>
}