import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useSelector } from 'react-redux';
import './store/order.css';
import imgAdd from './comment.png';

const Comments = () => {
    const [query, setQuery] = useState('c.id,comment,username kursovaya."Comments" c JOIN kursovaya."Users_catalog" u ON c.uder_id=u.id ORDER BY c.id DESC');
    const [results, setResults] = useState([]);
    const [comment, setComm] = useState('');
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');

    const isAuthenticated = useSelector((state) => state.user.isAuthenticated);

    const fetchData = async () => {
        setError('');
        try {
            const response = await axios.get('https://kursovaya.local/comments.php', {
                params: { query },
                headers: {
                    'Content-Type': 'application/json',
                },
            });
            setResults(response.data.data || []);
        } catch (err) {
            setError('Произошла ошибка при поиске.');
            console.error('Ошибка при выполнении запроса:', err.response ? err.response.data : err.message);
        }
    };

    const handleComment = async (id) => {
        try {
            const response = await axios.get('https://kursovaya.local/deleteComments.php', {
                params: { id },
                withCredentials: true,
            });
            const result = response.data;
            if (result.success) {
                alert('Комментарий успешно удалён!');
                fetchData();
            } else {
                alert('Не удалось удалить комментарий. Попробуйте снова.');
            }
        } catch (error) {
            console.error('Ошибка:', error);
            alert('Произошла ошибка. Попробуйте позже.');
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.get('https://kursovaya.local/addComments.php', {
                params: { comment },
                withCredentials: true,
            });
            if (response.data.success) {
                alert('Отзыв успешно отправлен!');
                setComm(''); // Очистить поле ввода после успешной отправки
                fetchData();
            } else {
                alert('Не удалось отправить отзыв. Попробуйте снова.');
            }
        } catch (error) {
            setError('Ошибка: ' + error.message);
            alert('Произошла ошибка. Попробуйте позже.');
        }
    };

    useEffect(() => {
        fetchData();
    }, [query]);

    return (
        <div>
            {isAuthenticated && (
                <form onSubmit={handleSubmit}>
                    <label>
                        <p className = "h_result">
                            Отзыв:
                        </p>
                        <input
                            type="text"
                            value={comment}
                            onChange={(e) => setComm(e.target.value)}
                            required
                        />
                    </label>
                    <button type="submit" className="in-button">
                        <img className='img_com' src={imgAdd} alt="Добавить комментарий" />
                    </button>
                </form>
            )}
            {error && <p style={{ color: 'red' }}>{error}</p>}
            {success && <p style={{ color: 'green' }}>{success}</p>}
            {results.map((comment) => {
                return (
                    <div key={comment.id} className="result_">
                        <p className="h_result">Пользователь: {comment.username}</p>
                        <p className="h_result">Отзыв: {comment.comment}</p>
                        {isAuthenticated && (
                            <button onClick={() => handleComment(comment.id)} className="main_order-button">Удалить комментарий</button>
                        )}
                    </div>
                );
            })}
        </div>
    );
};

export default Comments;
