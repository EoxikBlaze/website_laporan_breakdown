"use client";

import * as React from "react";
import { cn } from "@/lib/utils";
import { Button } from "@/Components/ui/button";

interface TimePickerProps {
    value?: Date;
    onChange?: (date: Date) => void;
}

export function TimePicker({ value, onChange }: TimePickerProps) {
    const hours = Array.from({ length: 24 }, (_, i) => i);
    const minutes = Array.from({ length: 60 }, (_, i) => i);

    const selectedHour = value?.getHours() ?? 0;
    const selectedMinute = value?.getMinutes() ?? 0;

    const hourRefs = React.useRef<(HTMLButtonElement | null)[]>([]);
    const minuteRefs = React.useRef<(HTMLButtonElement | null)[]>([]);

    React.useEffect(() => {
        // Scroll selected hour into view
        const hourEl = hourRefs.current[selectedHour];
        if (hourEl) {
            hourEl.scrollIntoView({ block: "center", behavior: "auto" });
        }
        // Scroll selected minute into view
        const minuteEl = minuteRefs.current[selectedMinute];
        if (minuteEl) {
            minuteEl.scrollIntoView({ block: "center", behavior: "auto" });
        }
    }, []);

    const handleHourChange = (hour: number) => {
        const newDate = new Date(value ?? new Date());
        newDate.setHours(hour);
        if (onChange) onChange(newDate);
    };

    const handleMinuteChange = (minute: number) => {
        const newDate = new Date(value ?? new Date());
        newDate.setMinutes(minute);
        if (onChange) onChange(newDate);
    };

    return (
        <div className="w-full bg-white rounded-2xl border border-neutral-100 overflow-hidden shadow-sm p-4">
            <div className="flex gap-8 justify-center">
                {/* Hours Column */}
                <div className="flex flex-col items-center gap-3">
                    <span className="text-[10px] uppercase font-bold text-neutral-400 tracking-widest">Jam</span>
                    <div className="flex flex-col h-[220px] overflow-y-auto p-2 w-14 no-scrollbar scroll-smooth space-y-2 snap-y snap-mandatory">
                        {hours.map((h) => (
                            <button
                                key={h}
                                ref={(el) => (hourRefs.current[h] = el)}
                                onClick={() => handleHourChange(h)}
                                className={cn(
                                    "h-10 w-10 flex-shrink-0 flex items-center justify-center text-sm font-bold rounded-xl transition-all duration-200 snap-center",
                                    selectedHour === h 
                                        ? "bg-blue-600 text-white shadow-lg shadow-blue-600/30 scale-110" 
                                        : "text-neutral-400 hover:bg-blue-50 hover:text-blue-600"
                                )}
                            >
                                {h.toString().padStart(2, "0")}
                            </button>
                        ))}
                    </div>
                </div>

                <div className="flex flex-col justify-center pt-6">
                    <span className="text-xl font-bold text-neutral-300 animate-pulse">:</span>
                </div>

                {/* Minutes Column */}
                <div className="flex flex-col items-center gap-3">
                    <span className="text-[10px] uppercase font-bold text-neutral-400 tracking-widest">Menit</span>
                    <div className="flex flex-col h-[220px] overflow-y-auto p-2 w-14 no-scrollbar scroll-smooth space-y-2 snap-y snap-mandatory">
                        {minutes.map((m) => (
                            <button
                                key={m}
                                ref={(el) => (minuteRefs.current[m] = el)}
                                onClick={() => handleMinuteChange(m)}
                                className={cn(
                                    "h-10 w-10 flex-shrink-0 flex items-center justify-center text-sm font-bold rounded-xl transition-all duration-200 snap-center",
                                    selectedMinute === m 
                                        ? "bg-blue-600 text-white shadow-lg shadow-blue-600/30 scale-110" 
                                        : "text-neutral-400 hover:bg-blue-50 hover:text-blue-600"
                                )}
                            >
                                {m.toString().padStart(2, "0")}
                            </button>
                        ))}
                    </div>
                </div>
            </div>
            
            <style dangerouslySetInnerHTML={{ __html: `
                .no-scrollbar::-webkit-scrollbar { display: none; }
                .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            `}} />
        </div>
    );
}
