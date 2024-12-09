import React, { useState } from 'react'
import myImage from './logo.png'
import './header.css'
import {Link} from 'react-router-dom'

function Header(){

    return (
        <div className = "back">
            <div className='header'>
                <div className='logo'>
                    <img className = "img_logo" src={myImage} alt="logo" />
                </div>
                <Link className = "a" to = "/"><h1 className = "h1">LIBRARY</h1></Link>
                <div className = "menu">
                    <div className = "login">
                        <Link className="a1" to={'login'}><h2 className="h">ВХОД</h2></Link>
                    </div>
                    <div className = "info">
                        <Link className="a1" to={'info'}><h2 className="h">О ПРОЕКТЕ</h2></Link>
                    </div>
                </div>
            </div>
        </div>
    );
}
export default Header