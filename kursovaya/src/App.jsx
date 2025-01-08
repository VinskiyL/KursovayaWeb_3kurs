import React, { useEffect } from 'react';
import MainContent from './components/main/main';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Authors from './components/authors/authors_table';
import './App.css';
import Header from './components/header/header';
import AboutMe from './components/aboutme';
import Login from './components/login';
import Prof from './components/profile';
import Books from './components/books/books_table';
import Error from './components/error/Error404';
import Footer from './components/footer/footer';
import BooksInfo from './components/books/books_info'; // Переименовано для соответствия
import AuthorsInfo from './components/authors/authors_info'; // Переименовано для соответствия
import { useSelector, useDispatch } from 'react-redux';
import Booking from './components/store/booking';
import Order from './components/store/order';
import New from './components/store/new_order';
import axios from 'axios'; // Добавлен импорт axios
import { login, logout } from './components/store/userSlice';

function App() {
    const dispatch = useDispatch();
    const isAuthenticated = useSelector((state) => state.user.isAuthenticated);
    const username = useSelector((state) => state.user.userInfo)

    useEffect(() => {
    const checkToken = async () => {
        try {
            const response = await axios.get('https://kursovaya.local/check.php', { withCredentials: true });

            if (response.data.success) {
                // Если проверка прошла успешно, сохраняем информацию о пользователе
                dispatch(login(username)); // Передаем информацию о пользователе
            } else {
                dispatch(logout()); // Вызываем logout, если аутентификация не удалась
            }
        } catch (err) {
            dispatch(logout()); // В случае ошибки тоже вызываем logout
        }
    };

    checkToken();
}, [dispatch]);

    return (
        <BrowserRouter>
            <div className="container">
                <Header />
                <div className="content">
                    <Routes>
                        {isAuthenticated ? (
                            <>
                                <Route path="/" element={<Prof />} />

                            </>
                        ) : (
                            <Route path="/" element={<MainContent />} />
                        )}
                        <Route path="/info" element={<AboutMe />} />
                        <Route path="/login" element={<Login />} />
                        <Route path="/authors_table" element={<Authors />} />
                        <Route path="/books_table" element={<Books />} />
                        <Route path="/books_info/:index" element={<BooksInfo />} />
                        <Route path="/authors_info/:id" element={<AuthorsInfo />} />
                        <Route path="/booking" element={<Booking />} />
                        <Route path="/order" element={<Order />} />
                        <Route path="/new_order" element={<New />} />
                        <Route path="*" element={<Error />} />
                    </Routes>
                </div>
                <Footer />
            </div>
        </BrowserRouter>
    );
}

export default App;

