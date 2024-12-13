import React, { useState } from 'react';
import axios from 'axios';
import { useDispatch } from 'react-redux';
import { login } from './store/userSlice';
import { useNavigate } from 'react-router-dom';
import imgIn from './in.png'
import './login.css'

function Login() {
    const [surname, setSurname] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const dispatch = useDispatch();
    const navigate = useNavigate(); // Хук для навигации

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            const response = await axios.get('https://kursovaya.local/login.php', {
                params: {
                    username: surname,
                    password: password,
                },
            });

            if (response.data.success) {
                // Сохранение пользователя в Redux
                dispatch(login({ surname })); // Здесь можно добавить другие данные о пользователе
                setSuccess('Вход успешен!');
                setError('');

                // Перенаправление на страницу профиля
                navigate(`/profile/:${surname}`); // Замените на путь к странице профиля
            } else {
                setError('Неверная фамилия или пароль.');
                setSuccess('');
            }
        } catch (err) {
            setError('Ошибка при выполнении запроса.');
            setSuccess('');
        }
    };

    return (
        <div>
            <h2 className = "h2">Вход</h2>
            <form onSubmit={handleSubmit} className = "in_form">
                <div>
                    <label className = "input_acc">
                        <p className = "h_result">
                            Логин:
                        </p>
                        <input
                            type="text"
                            value={surname}
                            onChange={(e) => setSurname(e.target.value)}
                            required
                        />
                    </label>
                </div>
                <div>
                    <label className = "input_acc">
                        <p className = "h_result">
                            Пароль:
                        </p>
                        <input
                            type="text"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            required
                        />
                    </label>
                </div>
                <button type="submit" className="in-button">
                    <img className='img_in' src={imgIn} alt="in" />
                </button>
            </form>
            {error && <p style={{ color: 'red' }}>{error}</p>}
            {success && <p style={{ color: 'green' }}>{success}</p>}
        </div>
    );
}

export default Login;

