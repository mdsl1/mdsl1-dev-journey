import { useNavigate, Link } from "react-router-dom";
import { useState } from 'react'
import { RiMenu5Fill } from "react-icons/ri";

export default function HeaderVertical() {
    const [isFixed, setIsFixed] = useState(false);
    
    const class_toggle = isFixed 
        ? "text-sky-300" 
        : "hover:translate-x-0 -translate-x-[18vw]";

    function fix_header() {
        setIsFixed(!isFixed);
    }

    return (
        <header className={`bg-blue-950 ${class_toggle} hover:text-sky-300 text-blue-950
            transition-all duration-300 ease-in-out 
            w-[20vw] h-screen fixed top-0 left-0
            flex-row items-center z-50 
            outline-double outline-offset-2 outline-sky-300
        `}>
            
            <div className="flex-col my-7 justify-baseline items-center">
                <h1 className="text-3xl text-center f-megrim">Nice Choice</h1>
            </div>
            
            <button 
                onClick={ () => fix_header() } 
                className={`bg-sky-600 rounded-full 
                w-17 h-17
                duration-300 transition-all 
                flex justify-center items-center cursor-pointer
                absolute top-1/2 left-2/2 -translate-x-1/2 -translate-y-1/2
            `}>
                <RiMenu5Fill className="text-4xl"/>
            </button>

            <ul className="flex-col justify-center w-[80%] ml-[3vw]">
                <li className="mt-3"><Link to="/" className="text-2xl hover:text-sky-600">Home</Link></li>
                <li className="mt-3"><Link to="/trabalhos" className="text-2xl hover:text-sky-600">Nosso Trabalho</Link></li>

                <li className="mt-3"><Link to="/sobre" className="text-2xl hover:text-sky-600">Sobre Nós</Link></li>
            </ul>
        </header>
    );
}