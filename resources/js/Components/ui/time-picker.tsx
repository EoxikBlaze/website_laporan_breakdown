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
        <div className="flex bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
            {/* Hours Column */}
            <div className="flex flex-col border-r border-gray-100">
                <div className="px-3 py-1 bg-gray-50 text-[10px] uppercase font-bold text-muted-foreground border-b border-gray-100 text-center">
                    Jam
                </div>
                <div className="flex flex-col h-[200px] overflow-y-auto p-1 w-16 scroll-smooth">
                    {hours.map((h) => (
                        <Button
                            key={h}
                            ref={(el) => (hourRefs.current[h] = el)}
                            variant={selectedHour === h ? "default" : "ghost"}
                            className={cn(
                                "h-8 w-8 p-0 flex-shrink-0 text-sm font-medium rounded-full mb-2 mx-auto transition-all duration-200",
                                selectedHour === h ? "shadow-lg shadow-primary/40 bg-primary text-white scale-110" : "hover:bg-primary/10 hover:text-primary"
                            )}
                            onClick={() => handleHourChange(h)}
                        >
                            {h.toString().padStart(2, "0")}
                        </Button>
                    ))}
                </div>
            </div>

            {/* Minutes Column */}
            <div className="flex flex-col">
                <div className="px-3 py-1 bg-gray-50 text-[10px] uppercase font-bold text-muted-foreground border-b border-gray-100 text-center">
                    Menit
                </div>
                <div className="flex flex-col h-[200px] overflow-y-auto p-1 w-16 scroll-smooth">
                    {minutes.map((m) => (
                        <Button
                            key={m}
                            ref={(el) => (minuteRefs.current[m] = el)}
                            variant={selectedMinute === m ? "default" : "ghost"}
                            className={cn(
                                "h-8 w-8 p-0 flex-shrink-0 text-sm font-medium rounded-full mb-2 mx-auto transition-all duration-200",
                                selectedMinute === m ? "shadow-lg shadow-primary/40 bg-primary text-white scale-110" : "hover:bg-primary/10 hover:text-primary"
                            )}
                            onClick={() => handleMinuteChange(m)}
                        >
                            {m.toString().padStart(2, "0")}
                        </Button>
                    ))}
                </div>
            </div>
        </div>
    );
}
