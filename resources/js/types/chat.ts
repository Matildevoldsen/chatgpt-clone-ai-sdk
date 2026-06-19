export interface Conversation {
    id: string;
    role: 'user' | 'assistant' | 'tool' | string;
    content: string;
    created_at: string | null;
}

export interface Chat {
    id: string;
    title: string;
    updated_at: string | null;
    messages?: Conversation[];
}
