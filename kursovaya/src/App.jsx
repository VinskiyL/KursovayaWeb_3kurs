import React from 'react'
import MainContent from './components/main/main'
import { BrowserRouter, Routes, Route } from 'react-router-dom'
import Authors from './components/authors/authors_table'
import './App.css'
import Header from './components/header/header'
import AboutMe from './components/aboutme'
import Login from './components/login'
import Prof from './components/profile'
import Books from './components/books/books_table'
import Error from './components/error/Error404'
import Footer from './components/footer/footer'
import Books_info from './components/books/books_info'
import Authors_info from './components/authors/authors_info'

function App() {
return(
    <>
        <BrowserRouter>
            <div className = "container">
            <Header/>
            <div className = "content">
            <Routes>
                <Route path = "/" element = {<MainContent/>}/>
                <Route path = "/info" element = {<AboutMe/>}/>
                <Route path = "/login" element = {<Login/>}/>
                <Route path = "/profile:user" element = {<Prof/>}/>
                <Route path = "/authors_table" element = {<Authors/>}/>
                <Route path = "/books_table" element = {<Books/>}/>
                <Route path = "/books_info/:index" element = {<Books_info/>}/>
                <Route path = "/authors_info/:id" element = {<Authors_info/>}/>
                <Route path = "*" element = {<Error/>}/>
            </Routes>
            </div>
            <Footer/>
            </div>
        </BrowserRouter>
    </>
    )
}

export default App
