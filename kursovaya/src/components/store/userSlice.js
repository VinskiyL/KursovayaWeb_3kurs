import { createSlice } from '@reduxjs/toolkit';

const userSlice = createSlice({
    name: 'user',
    initialState: {
        userInfo: null,
        isAuthenticated: false,
    },
    reducers: {
        login: (state, action) => {
            state.userInfo = action.payload;
            state.isAuthenticated = true;
        },
        logout: (state) => {
            state.userInfo = null;
            state.isAuthenticated = false;
        },
    },
});

export const { login, logout } = userSlice.actions;
export default userSlice.reducer;
